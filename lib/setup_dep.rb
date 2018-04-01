class SetupDep
  attr_reader :name, :type, :path

  def initialize(name:, type:)
    @name = name
    @type = type
    @path = which(name)
  end

  def file?
    type == :file
  end

  def exec?
    type == :exec
  end

  def exists?
    case type
    when :exec
      !path.empty?
    when :file
      File.exist?(name)
    else
      false
    end
  end

  def which(exec)
    `which #{exec}`.strip
  end
end
